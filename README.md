## AI Integration

Provider: OpenAI `gpt-4o-mini` (falls back to mock if `OPENAI_API_KEY` is not set).

### Prompt template

Given a task titled "{title}" with description "{description}",
priority "{priority}", status "{status}", and due date "{due_date}",
return a JSON object with:
- "summary": 1-2 sentence plain-English summary of the task and its urgency.
- "priority": recommended priority (low | medium | high).

### Mock fallback

When no API key is configured, AIService returns a deterministic mock
that mirrors the task's existing priority. All architecture contracts are
preserved — swapping in any LLM provider requires only changing AIService.