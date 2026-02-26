<?php

namespace App\View\Components;

use App\Models\Institution;
use App\Models\Oferta;
use App\Models\School;
use App\Models\User;
use App\Models\Questionario;
use App\Models\QuestionarioOferta;
use App\Models\Estudante;
use App\Models\Frequencia;
use App\Models\RespostaQuestionario;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dashboard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $user = User::count();
        view()->share('user',$user);

        $institution = Institution::count();
        view()->share('institution',$institution);

        $school = School::count();
        view()->share('school',$school);

        $oferta = Oferta::count();
        view()->share('oferta',$oferta);



        $questionario = Questionario::count();
        view()->share('questionario',$questionario);

        $questionarioOferta = QuestionarioOferta::count();
        view()->share('questionarioOferta',$questionarioOferta);

        // Dados para relatórios básicos
        $totalEstudantes = Estudante::count();
        view()->share('totalEstudantes', $totalEstudantes);

        $totalFrequencias = Frequencia::count();
        view()->share('totalFrequencias', $totalFrequencias);

        // Calcular estudantes em risco (considerando tempo decorrido)
        $estudantesRisco = 0;
        $percentualMinimo = 75;
        $estudantes = Estudante::with(['disciplinas', 'frequencias'])->get();
        foreach ($estudantes as $estudante) {
            $totalHorasEsperadas = 0;
            $horasFrequentes = 0;

            foreach ($estudante->disciplinas as $disciplina) {
                // Calcular percentual de tempo decorrido da disciplina
                $percentualTempoDecorrido = $disciplina->getPercentualTempoDecorrido();

                // Se a disciplina ainda não começou, não considerar
                if ($percentualTempoDecorrido <= 0) {
                    continue;
                }

                // Calcular horas esperadas até agora (baseado no tempo decorrido)
                $horasEsperadasAteAgora = ($disciplina->carga_horaria_total * $percentualTempoDecorrido) / 100;

                // Calcular horas mínimas esperadas (aplicando o percentual mínimo)
                $horasMinimasEsperadas = ($horasEsperadasAteAgora * $percentualMinimo) / 100;

                // Buscar frequências do estudante nesta disciplina
                $frequencias = $estudante->frequencias()
                    ->where('disciplina_id', $disciplina->id)
                    ->get();

                $horasDisciplina = $frequencias->sum('hora_aula');

                $totalHorasEsperadas += $horasMinimasEsperadas;
                $horasFrequentes += $horasDisciplina;
            }

            // Se não há disciplinas em andamento, não considerar em risco
            if ($totalHorasEsperadas == 0) {
                continue;
            }

            // Calcular percentual: horas frequentes vs horas mínimas esperadas
            $percentual = $totalHorasEsperadas > 0 ? ($horasFrequentes / $totalHorasEsperadas) * 100 : 0;

            // Está em risco se não atingiu o mínimo esperado até agora
            if ($percentual < 100) {
                $estudantesRisco++;
            }
        }
        view()->share('estudantesRisco', $estudantesRisco);

        $totalRespostas = RespostaQuestionario::count();
        view()->share('totalRespostas', $totalRespostas);

        // Dados para gráficos
        // Frequência por mês (últimos 6 meses)
        $frequenciaPorMes = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = \Carbon\Carbon::now()->subMonths($i);
            $mesInicio = $mes->copy()->startOfMonth();
            $mesFim = $mes->copy()->endOfMonth();

            $total = Frequencia::whereBetween('data_aula', [$mesInicio, $mesFim])
                ->sum('hora_aula');

            $frequenciaPorMes[] = [
                'mes' => $mes->format('M/Y'),
                'total' => $total
            ];
        }
        view()->share('frequenciaPorMes', $frequenciaPorMes);

        // Distribuição de estudantes por oferta (top 5)
        $estudantesPorOferta = Oferta::withCount('estudantes')
            ->orderBy('estudantes_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($oferta) {
                return [
                    'nome' => $oferta->name,
                    'total' => $oferta->estudantes_count
                ];
            });
        view()->share('estudantesPorOferta', $estudantesPorOferta);

        // Status de frequência (Normal vs Risco)
        $totalEstudantesComFrequencia = Estudante::whereHas('frequencias')->count();
        $estudantesNormal = $totalEstudantesComFrequencia - $estudantesRisco;
        view()->share('estudantesNormal', $estudantesNormal);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard');
    }
}
