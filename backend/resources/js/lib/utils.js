import { clsx } from 'clsx'
import { twMerge } from 'tailwind-merge'

export function cn(...inputs) {
  return twMerge(clsx(inputs))
}

/**
 * Convert rich-text/HTML (from the editor, or a translatable JSON value
 * serialized to the current locale) into plain text for table/inline display.
 * Strips tags and decodes entities so "<p>koeuk</p>" renders as "koeuk".
 */
export function stripHtml(value) {
  if (value == null) return ''
  const html = String(value)
  if (typeof document !== 'undefined') {
    const tmp = document.createElement('div')
    tmp.innerHTML = html
    return (tmp.textContent || tmp.innerText || '').trim()
  }
  return html.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim()
}
