<?php

namespace App\Http\Controllers;

use App\Models\Estudante;
use App\Models\Oferta;
use App\Models\Disciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EstudanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudantes = Estudante::with('oferta')->orderBy('id', 'DESC')->get();
        return view('admin.estudantes.index', compact('estudantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ofertas = Oferta::all();
        return view('admin.estudantes.create', compact('ofertas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|max:255',
            'cpf' => 'required|string|max:14|unique:estudantes,cpf',
            'data_nascimento' => 'nullable|date',
            'matricula' => 'nullable|max:255',
            'nome_mae' => 'nullable|max:255',
            'cep' => 'nullable|max:10',
            'telefone' => 'nullable|max:20',
            'email' => 'nullable|email|max:255',
            'oferta_id' => 'required|exists:ofertas,id',
        ]);

        Estudante::create($request->all());

        return redirect()->route('admin.estudante.index')->with('success', 'Estudante criado com sucesso.');
    }

    /**
     * Show the form for uploading a spreadsheet.
     */
    public function uploadForm()
    {
        $ofertas = Oferta::all();
        return view('admin.estudantes.upload', compact('ofertas'));
    }

    /**
     * Process uploaded spreadsheet.
     */
    public function processUpload(Request $request)
    {
        $request->validate([
            'oferta_id' => 'required|exists:ofertas,id',
            'disciplina_id' => 'nullable|exists:disciplinas,id',
            'arquivo' => 'required|file|mimes:csv,txt,xls,xlsx|max:10240',
        ]);

        $file = $request->file('arquivo');
        $ofertaId = $request->oferta_id;
        $disciplinaId = $request->disciplina_id;
        $extension = $file->getClientOriginalExtension();

        try {
            $resultados = [];
            if (in_array($extension, ['csv', 'txt'])) {
                $resultados = $this->processCsv($file, $ofertaId, $disciplinaId);
            } elseif (in_array($extension, ['xls', 'xlsx'])) {
                $resultados = $this->processExcel($file, $ofertaId, $disciplinaId);
            } else {
                return redirect()->back()->with('error', 'Formato de arquivo não suportado.');
            }

            // Gerar token único para esta importação
            $importToken = uniqid('import_', true);

            // Armazenar resultados na sessão com token
            session([
                'import_resultados' => $resultados,
                'import_oferta_id' => $ofertaId,
                'import_disciplina_id' => $disciplinaId,
                'import_token' => $importToken,
            ]);

            // Redirecionar para página de resultados com token
            return redirect()->route('admin.estudante.import-resultados', ['token' => $importToken]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao processar arquivo: ' . $e->getMessage());
        }
    }

    /**
     * Get disciplines by offer (AJAX).
     */
    public function getDisciplinasByOferta($ofertaId)
    {
        $disciplinas = Disciplina::where('oferta_id', $ofertaId)->get();
        return response()->json($disciplinas);
    }

    /**
     * Show import results page.
     */
    public function importResultados(Request $request)
    {
        $token = $request->get('token');
        $sessionToken = session('import_token');

        // Verificar se o token da URL corresponde ao da sessão
        if (!$token || $token !== $sessionToken) {
            // Limpar dados da sessão se o token não corresponder
            session()->forget(['import_resultados', 'import_oferta_id', 'import_disciplina_id', 'import_token']);
            return redirect()->route('admin.estudante.index')
                ->with('error', 'Sessão de importação inválida ou expirada.');
        }

        $resultados = session('import_resultados', []);
        $ofertaId = session('import_oferta_id');
        $disciplinaId = session('import_disciplina_id');

        $oferta = $ofertaId ? Oferta::find($ofertaId) : null;
        $disciplina = $disciplinaId ? Disciplina::find($disciplinaId) : null;

        if (empty($resultados)) {
            // Limpar dados da sessão
            session()->forget(['import_resultados', 'import_oferta_id', 'import_disciplina_id', 'import_token']);
            return redirect()->route('admin.estudante.index')
                ->with('error', 'Nenhum resultado de importação encontrado.');
        }

        // Limpar dados da sessão após exibir (para evitar reimportação ao recarregar)
        session()->forget(['import_resultados', 'import_oferta_id', 'import_disciplina_id', 'import_token']);

        return view('admin.estudantes.import-resultados', compact('resultados', 'oferta', 'disciplina', 'token'));
    }

    /**
     * Process CSV file.
     */
    private function processCsv($file, $ofertaId, $disciplinaId = null)
    {
        $handle = fopen($file->getRealPath(), 'r');

        // Detectar delimitador (tenta ; primeiro, depois ,)
        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

        // Pular cabeçalho se existir
        $header = fgetcsv($handle, 0, $delimiter);

            $linha = 1;
            $resultados = [
                'sucessos' => [],
                'warnings' => [],
                'erros' => [],
                'total_processado' => 0,
            ];

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                $linha++;

                // Limpar e converter encoding se necessário
                $data = array_map(function($field) {
                    $field = trim($field);
                    // Converter de ISO-8859-1 para UTF-8 se necessário
                    if (!mb_check_encoding($field, 'UTF-8')) {
                        $field = mb_convert_encoding($field, 'UTF-8', 'ISO-8859-1');
                    }
                    return $field;
                }, $data);

                $resultado = $this->processEstudanteData($data, $ofertaId, $disciplinaId, $linha);
                $resultados['total_processado']++;

                if ($resultado['sucesso']) {
                    $resultados['sucessos'][] = $resultado;
                } elseif (isset($resultado['warning']) && $resultado['warning']) {
                    $resultados['warnings'][] = $resultado;
                } else {
                    $resultados['erros'][] = $resultado;
                }
            }

            fclose($handle);
            DB::commit();

            return $resultados;
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
            throw $e;
        }
    }

    /**
     * Process Excel file (XLS/XLSX).
     */
    private function processExcel($file, $ofertaId, $disciplinaId = null)
    {
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            $linha = 0;
            $resultados = [
                'sucessos' => [],
                'warnings' => [],
                'erros' => [],
                'total_processado' => 0,
            ];

            DB::beginTransaction();
            try {
                foreach ($rows as $row) {
                    $linha++;

                    // Pular cabeçalho (primeira linha)
                    if ($linha === 1) {
                        continue;
                    }

                    // Converter valores para string e limpar
                    $data = array_map(function($cell) {
                        if ($cell instanceof \DateTime) {
                            return $cell->format('d/m/Y');
                        }
                        $value = trim((string) $cell);
                        // Converter encoding se necessário
                        if (!mb_check_encoding($value, 'UTF-8')) {
                            $value = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
                        }
                        return $value;
                    }, $row);

                    $resultado = $this->processEstudanteData($data, $ofertaId, $disciplinaId, $linha);
                    $resultados['total_processado']++;

                    if ($resultado['sucesso']) {
                        $resultados['sucessos'][] = $resultado;
                    } elseif (isset($resultado['warning']) && $resultado['warning']) {
                        $resultados['warnings'][] = $resultado;
                    } else {
                        $resultados['erros'][] = $resultado;
                    }
                }

                DB::commit();

                return $resultados;
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            throw new \Exception('Erro ao ler arquivo Excel: ' . $e->getMessage());
        }
    }

    /**
     * Process estudante data (common for CSV and Excel).
     */
    private function processEstudanteData($data, $ofertaId, $disciplinaId, $linha)
    {
        // Mapear colunas (ajuste conforme o formato da planilha)
        // Esperado: nome, cpf, data_nascimento, matricula, nome_mae, cep, telefone, email
        $estudanteData = [
            'nome' => $data[0] ?? '',
            'cpf' => $this->formatarCpf($data[1] ?? ''),
            'data_nascimento' => $this->formatarData($data[2] ?? null),
            'matricula' => $data[3] ?? null,
            'nome_mae' => $data[4] ?? null,
            'cep' => $data[5] ?? null,
            'telefone' => $data[6] ?? null,
            'email' => $data[7] ?? null,
            'oferta_id' => $ofertaId,
        ];

        $resultado = [
            'linha' => $linha,
            'nome' => $estudanteData['nome'],
            'cpf' => $estudanteData['cpf'],
            'matricula' => $estudanteData['matricula'],
            'email' => $estudanteData['email'],
            'sucesso' => false,
            'mensagem' => '',
        ];

        // Validar dados obrigatórios
        if (empty($estudanteData['nome']) && empty($estudanteData['cpf'])) {
            $resultado['mensagem'] = 'Nome e CPF são obrigatórios. Ambos os campos estão vazios.';
            return $resultado;
        }

        if (empty($estudanteData['nome'])) {
            $resultado['mensagem'] = 'Nome é obrigatório. Campo não preenchido na planilha.';
            return $resultado;
        }

        if (empty($estudanteData['cpf'])) {
            $resultado['mensagem'] = 'CPF é obrigatório. Campo não preenchido na planilha.';
            return $resultado;
        }

        // Validar formato do CPF
        $cpfLimpo = preg_replace('/[^0-9]/', '', $estudanteData['cpf']);
        if (strlen($cpfLimpo) != 11) {
            $resultado['mensagem'] = 'CPF inválido. Deve conter 11 dígitos numéricos.';
            return $resultado;
        }

        // Verificar se CPF já existe
        $estudanteExistente = Estudante::where('cpf', $estudanteData['cpf'])->where('oferta_id', $ofertaId)->first();

        if ($estudanteExistente) {
            // Estudante já existe - vincular às disciplinas se necessário
            try {
                $disciplinasVinculadas = [];

                if ($disciplinaId) {
                    // Verificar se já está vinculado à disciplina específica
                    $jaVinculado = $estudanteExistente->disciplinas()->where('disciplina_id', $disciplinaId)->exists();

                    if (!$jaVinculado) {
                        // Vincular à disciplina específica
                        $estudanteExistente->disciplinas()->attach($disciplinaId);
                        $disciplina = Disciplina::find($disciplinaId);
                        $resultado['disciplina_nome'] = $disciplina ? $disciplina->nome : 'Disciplina não encontrada';
                        $resultado['sucesso'] = true;
                        $resultado['mensagem'] = 'Estudante existente, mas vinculado a nova disciplina';
                    } else {
                        // Já está vinculado
                        $disciplina = Disciplina::find($disciplinaId);
                        $resultado['disciplina_nome'] = $disciplina ? $disciplina->nome : 'Disciplina não encontrada';
                        $resultado['sucesso'] = false;
                        $resultado['warning'] = true;
                        $resultado['mensagem'] = 'Estudante já existente e já vinculado à disciplina';
                    }
                } else {
                    // Vincular a todas as disciplinas da oferta que ainda não estão vinculadas
                    $disciplinas = Disciplina::where('oferta_id', $ofertaId)->get();
                    $disciplinasJaVinculadas = $estudanteExistente->disciplinas()->pluck('disciplina_id')->toArray();
                    $disciplinasParaVincular = $disciplinas->pluck('id')->diff($disciplinasJaVinculadas)->toArray();

                    if (!empty($disciplinasParaVincular)) {
                        $estudanteExistente->disciplinas()->attach($disciplinasParaVincular);
                        $nomesDisciplinas = $disciplinas->whereIn('id', $disciplinasParaVincular)->pluck('nome')->toArray();
                        if (count($nomesDisciplinas) <= 3) {
                            $resultado['disciplina_nome'] = 'Vinculado a (' . count($nomesDisciplinas) . '): ' . implode(', ', $nomesDisciplinas);
                        } else {
                            $resultado['disciplina_nome'] = 'Vinculado a ' . count($nomesDisciplinas) . ' disciplinas';
                        }
                        $resultado['sucesso'] = true;
                        $resultado['mensagem'] = 'Estudante existente, mas vinculado as novas disciplinas';
                    } else {
                        // Já está vinculado a todas as disciplinas
                        $nomesDisciplinas = $disciplinas->pluck('nome')->toArray();
                        if (count($nomesDisciplinas) <= 3) {
                            $resultado['disciplina_nome'] = 'Todas (' . count($nomesDisciplinas) . '): ' . implode(', ', $nomesDisciplinas);
                        } else {
                            $resultado['disciplina_nome'] = 'Todas as ' . count($nomesDisciplinas) . ' disciplinas da oferta';
                        }
                        $resultado['sucesso'] = false;
                        $resultado['warning'] = true;
                        $resultado['mensagem'] = 'Estudante já existente e já vinculado a todas as disciplinas';
                    }
                }

                $resultado['estudante_id'] = $estudanteExistente->id;
                $resultado['nome'] = $estudanteExistente->nome;
                $resultado['matricula'] = $estudanteExistente->matricula;
                $resultado['email'] = $estudanteExistente->email;

                return $resultado;
            } catch (\Exception $e) {
                $resultado['mensagem'] = 'Erro ao vincular estudante existente: ' . $e->getMessage();
                return $resultado;
            }
        }

        // Estudante não existe - criar novo
        try {
            $estudante = Estudante::create($estudanteData);

            // Vincular estudante às disciplinas
            if ($disciplinaId) {
                // Vincular à disciplina específica
                $estudante->disciplinas()->attach($disciplinaId);
            } else {
                // Vincular a todas as disciplinas da oferta
                $disciplinas = Disciplina::where('oferta_id', $ofertaId)->pluck('id');
                if ($disciplinas->isNotEmpty()) {
                    $estudante->disciplinas()->attach($disciplinas->toArray());
                }
            }

            $resultado['sucesso'] = true;
            $resultado['mensagem'] = 'Importado com sucesso';
            $resultado['estudante_id'] = $estudante->id;

            // Adicionar informações sobre disciplinas vinculadas
            if ($disciplinaId) {
                $disciplina = Disciplina::find($disciplinaId);
                $resultado['disciplina_nome'] = $disciplina ? $disciplina->nome : 'Disciplina não encontrada';
            } else {
                $disciplinas = Disciplina::where('oferta_id', $ofertaId)->get();
                if ($disciplinas->isNotEmpty()) {
                    $nomesDisciplinas = $disciplinas->pluck('nome')->toArray();
                    if (count($nomesDisciplinas) <= 3) {
                        $resultado['disciplina_nome'] = 'Todas (' . count($nomesDisciplinas) . '): ' . implode(', ', $nomesDisciplinas);
                    } else {
                        $resultado['disciplina_nome'] = 'Todas as ' . count($nomesDisciplinas) . ' disciplinas da oferta';
                    }
                } else {
                    $resultado['disciplina_nome'] = 'Nenhuma disciplina disponível';
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                $resultado['mensagem'] = 'Erro: Registro duplicado. Este estudante já existe no sistema.';
            } else {
                $resultado['mensagem'] = 'Erro ao salvar no banco de dados: ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $resultado['mensagem'] = 'Erro inesperado: ' . $e->getMessage();
        }

        return $resultado;
    }

    /**
     * Format CPF.
     */
    private function formatarCpf($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) == 11) {
            return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
        }
        return $cpf;
    }

    /**
     * Format date.
     */
    private function formatarData($data)
    {
        if (empty($data)) {
            return null;
        }

        // Tenta vários formatos comuns
        $formatos = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'Y/m/d'];
        foreach ($formatos as $formato) {
            $date = \DateTime::createFromFormat($formato, $data);
            if ($date) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $estudanteId = decrypt($id);
        } catch (DecryptException $e) {
            // Se falhar a descriptografia, tenta usar o ID diretamente
            $estudanteId = $id;
        }

        $estudante = Estudante::with(['oferta', 'disciplinas'])->findOrFail($estudanteId);
        return view('admin.estudantes.show', compact('estudante'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $estudanteId = decrypt($id);
        } catch (DecryptException $e) {
            $estudanteId = $id;
        }

        $estudante = Estudante::findOrFail($estudanteId);
        $ofertas = Oferta::all();
        return view('admin.estudantes.edit', compact('estudante', 'ofertas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $estudanteId = decrypt($id);
        } catch (DecryptException $e) {
            $estudanteId = $id;
        }

        $estudante = Estudante::findOrFail($estudanteId);

        $request->validate([
            'nome' => 'required|max:255',
            'cpf' => 'required|string|max:14|unique:estudantes,cpf,' . $estudante->id,
            'data_nascimento' => 'nullable|date',
            'matricula' => 'nullable|max:255',
            'nome_mae' => 'nullable|max:255',
            'cep' => 'nullable|max:10',
            'telefone' => 'nullable|max:20',
            'email' => 'nullable|email|max:255',
            'oferta_id' => 'required|exists:ofertas,id',
        ]);

        $estudante->update($request->all());

        return redirect()->route('admin.estudante.index')->with('success', 'Estudante atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $estudanteId = decrypt($id);
        } catch (DecryptException $e) {
            $estudanteId = $id;
        }

        $estudante = Estudante::findOrFail($estudanteId);
        $estudante->delete();

        return redirect()->route('admin.estudante.index')->with('success', 'Estudante removido com sucesso.');
    }
}

