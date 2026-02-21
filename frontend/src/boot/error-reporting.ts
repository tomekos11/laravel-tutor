import { defineBoot } from '@quasar/app-vite/wrappers';

type ClientErrorPayload = {
  type: string;
  message: string;
  stack?: string | undefined;
  url?: string | undefined;
};

function reportClientError(payload: ClientErrorPayload) {
  const base = typeof import.meta.env.BASE_URL === 'string' ? import.meta.env.BASE_URL : '/';
  const path = base.endsWith('/') ? base + 'api/client-error' : base + '/api/client-error';
  const url = typeof window !== 'undefined' ? `${window.location.origin}${path}` : '';

  if (!url) return;

  void fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload),
    keepalive: true,
  }).catch(() => {
    // Nie loguj w konsoli – unikamy pętli przy błędach sieci
  });
}

export default defineBoot(({ app }) => {
  if (typeof window === 'undefined') return;

  app.config.errorHandler = (err, instance, info) => {
    const message = err instanceof Error ? err.message : String(err);
    const stack = err instanceof Error ? err.stack : undefined;

    reportClientError({
      type: 'vue-errorHandler',
      message,
      stack,
      url: `${info ?? 'unknown'}`,
    });
  };

  window.addEventListener('error', (event: ErrorEvent) => {
    reportClientError({
      type: 'window.onerror',
      message: event.message ?? event.error?.message ?? 'Unknown error',
      stack: event.error?.stack,
      url: event.filename ?? window.location.href,
    });
  });

  window.addEventListener('unhandledrejection', (event) => {
    const reason = event.reason;
    const message = reason instanceof Error ? reason.message : String(reason);
    const stack = reason instanceof Error ? reason.stack : undefined;

    reportClientError({
      type: 'unhandledrejection',
      message,
      stack,
      url: window.location.href,
    });
  });
});
