<?php
namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    public function generateSummary(Task $task): array
    {
        try {
            if (!config('services.openai.key')) {
                return $this->mockResponse($task);
            }

            $prompt = $this->buildPrompt($task);
            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'       => 'gpt-4o-mini',
                    'max_tokens'  => 200,
                    'messages'    => [
                        ['role' => 'system', 'content' => 'You are a project management AI. Respond only with valid JSON.'],
                        ['role' => 'user',   'content' => $prompt],
                    ],
                ]);

            $data = json_decode($response->json('choices.0.message.content'), true);

            return [
                'ai_summary'  => $data['summary']  ?? 'No summary available.',
                'ai_priority' => in_array($data['priority'] ?? '', ['low', 'medium', 'high'])
                    ? $data['priority']
                    : 'medium',
            ];
        } catch (\Throwable $e) {
            Log::warning('AIService failed, using mock', ['error' => $e->getMessage()]);
            return $this->mockResponse($task);
        }
    }

    /**
     * Prompt template:
     * Given a task titled "{title}" with description "{description}",
     * priority "{priority}", status "{status}", and due date "{due_date}",
     * return a JSON object with two keys:
     *   - "summary": a 1-2 sentence plain-English summary of the task and its urgency.
     *   - "priority": your recommended priority (low | medium | high) based on content and due date.
     */
    private function buildPrompt(Task $task): string
    {
        return <<<PROMPT
            Given a task titled "{$task->title}" with description "{$task->description}",
            priority "{$task->priority->value}", status "{$task->status->value}",
            and due date "{$task->due_date?->toDateString()}",
            return a JSON object with:
            - "summary": 1-2 sentence plain-English summary of the task and its urgency.
            - "priority": recommended priority (low | medium | high) based on content and due date.
            PROMPT;
    }

    private function mockResponse(Task $task): array
    {
        return [
            'ai_summary'  => "Task \"{$task->title}\" requires attention. Based on its description and due date, timely completion is recommended.",
            'ai_priority' => $task->priority->value,
        ];
    }
}