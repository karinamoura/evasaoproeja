<?php

namespace Database\Seeders;

use App\Models\Questionario;
use App\Models\Pergunta;
use App\Models\OpcaoResposta;
use App\Models\Secao;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar/obter questionário de satisfação (idempotente)
        $questionario = Questionario::firstOrCreate(
            ['slug' => 'questionario-satisfacao-aluno'],
            [
                'titulo' => 'Questionário de Satisfação do Aluno',
                'descricao' => 'Avalie sua experiência com o curso e nos ajude a melhorar.',
                'ativo' => true
            ]
        );

        // Limpar dados anteriores do questionário (perguntas e seções) para evitar duplicidade
        $questionario->perguntas()->delete();
        $questionario->secoes()->delete();

        // Criar seções
        $secaoInformacoes = $questionario->secoes()->create([
            'titulo' => 'Informações Pessoais',
            'descricao' => 'Dados básicos do respondente',
            'ordem' => 1,
        ]);

        $secaoAvaliacao = $questionario->secoes()->create([
            'titulo' => 'Avaliação do Curso',
            'descricao' => 'Opinião sobre o curso e recursos',
            'ordem' => 2,
        ]);

        // Pergunta 1 - Texto simples
        $pergunta1 = $questionario->perguntas()->create([
            'pergunta' => 'Qual é o seu nome completo?',
            'tipo' => 'texto_simples',
            'obrigatoria' => true,
            'ordem' => 1,
            'formato_validacao' => 'texto_comum',
            'secao_id' => $secaoInformacoes->id,
        ]);

        // Pergunta 2 - Texto longo
        $pergunta2 = $questionario->perguntas()->create([
            'pergunta' => 'Descreva sua experiência geral com o curso:',
            'tipo' => 'texto_longo',
            'obrigatoria' => false,
            'ordem' => 2,
            'secao_id' => $secaoInformacoes->id,
        ]);

        // Pergunta 3 - Radio (única escolha)
        $pergunta3 = $questionario->perguntas()->create([
            'pergunta' => 'Como você avalia a qualidade do conteúdo do curso?',
            'tipo' => 'radio',
            'obrigatoria' => true,
            'ordem' => 3,
            'secao_id' => $secaoAvaliacao->id,
        ]);

        // Opções para pergunta 3
        $opcoes3 = ['Excelente', 'Bom', 'Regular', 'Ruim', 'Muito ruim'];
        foreach ($opcoes3 as $index => $opcao) {
            $pergunta3->opcoesResposta()->create([
                'opcao' => $opcao,
                'ordem' => $index + 1
            ]);
        }

        // Pergunta 4 - Checkbox (múltipla escolha)
        $pergunta4 = $questionario->perguntas()->create([
            'pergunta' => 'Quais recursos você mais utilizou durante o curso? (Selecione todas as opções aplicáveis)',
            'tipo' => 'checkbox',
            'obrigatoria' => false,
            'ordem' => 4,
            'secao_id' => $secaoAvaliacao->id,
        ]);

        // Opções para pergunta 4
        $opcoes4 = ['Material didático', 'Vídeo aulas', 'Fórum de discussão', 'Tutoria individual', 'Biblioteca virtual'];
        foreach ($opcoes4 as $index => $opcao) {
            $pergunta4->opcoesResposta()->create([
                'opcao' => $opcao,
                'ordem' => $index + 1
            ]);
        }

        // Pergunta 5 - Select
        $pergunta5 = $questionario->perguntas()->create([
            'pergunta' => 'Qual é a sua área de estudo principal?',
            'tipo' => 'select',
            'obrigatoria' => true,
            'ordem' => 5,
            'secao_id' => $secaoAvaliacao->id,
        ]);

        // Opções para pergunta 5
        $opcoes5 = ['Ciências Exatas', 'Ciências Humanas', 'Ciências Biológicas', 'Tecnologia', 'Artes', 'Outras'];
        foreach ($opcoes5 as $index => $opcao) {
            $pergunta5->opcoesResposta()->create([
                'opcao' => $opcao,
                'ordem' => $index + 1
            ]);
        }

        // Não criar questionários adicionais via factory
        // Apenas o questionário base de satisfação foi criado acima
    }
}
