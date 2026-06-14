import * as React from 'react';

export interface BadgeProps {
  children?: React.ReactNode;
  /** Invoice-status palette (takes priority over `tone`). */
  status?: 'paid' | 'awaiting' | 'overdue' | 'draft' | 'sent';
  /** Generic soft tone when not an invoice status. */
  tone?: 'success' | 'danger' | 'neutral' | 'primary';
  /** Pill vs. rounded-rect. @default true */
  rounded?: boolean;
  /** Show a leading status dot. */
  dot?: boolean;
  style?: React.CSSProperties;
}

/**
 * Status pill — the invoice-state Tag used in tables, dashboard and lists.
 *
 * @startingPoint section="Core" subtitle="Invoice status pills" viewport="700x140"
 */
export function Badge(props: BadgeProps): React.JSX.Element;
