/**
 * Endpoint do raportowania błędów z klienta (Vue errorHandler, window.onerror).
 * Logowane w Winston po stronie SSR.
 */
import express from 'express';
import { type Request, type Response } from 'express';
import { defineSsrMiddleware } from '#q-app/wrappers';
import { logger } from '../logger.js';

export default defineSsrMiddleware(({ app, resolve }) => {
  const path = resolve.urlPath('api/client-error');
  app.post(path, express.json(), (req: Request, res: Response) => {
    const body = req.body as Record<string, unknown> | undefined;
    const message = (body?.message as string) ?? 'Client error';
    const stack = body?.stack as string | undefined;
    const url = body?.url as string | undefined;
    const type = (body?.type as string) ?? 'client';

    logger.error('Client-side error reported', {
      type,
      message,
      stack,
      url,
      userAgent: req.get('user-agent'),
    });

    res.status(204).end();
  });
});
