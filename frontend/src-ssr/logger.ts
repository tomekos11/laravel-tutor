/**
 * Winston logger for SSR (Node.js only).
 * Used in src-ssr/server.ts and middlewares.
 */
import winston from 'winston';

const { combine, timestamp, printf, errors } = winston.format;

const logFormat = printf(({ level, message, timestamp: ts, stack, ...meta }: Record<string, unknown>) => {
  const metaStr = Object.keys(meta).length ? ' ' + JSON.stringify(meta) : '';
  const stackStr =
    stack == null ? '' : `\n${typeof stack === 'string' ? stack : JSON.stringify(stack)}`;
  return `${String(ts)} [${String(level)}] ${String(message)}${metaStr}${stackStr}`;
});

export const logger = winston.createLogger({
  level: process.env.LOG_LEVEL ?? (process.env.PROD ? 'info' : 'debug'),
  format: combine(
    errors({ stack: true }),
    timestamp({ format: 'YYYY-MM-DD HH:mm:ss' }),
    logFormat
  ),
  defaultMeta: { service: 'ssr' },
  transports: [
    new winston.transports.Console(),
    // W produkcji można dodać zapis do pliku:
    new winston.transports.File({ filename: 'logs/ssr-error.log', level: 'error' }),
  ],
});
