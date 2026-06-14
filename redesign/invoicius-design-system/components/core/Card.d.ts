import * as React from 'react';

export interface CardProps {
  children?: React.ReactNode;
  /** Optional header title; renders a divided header row. */
  title?: React.ReactNode;
  subtitle?: React.ReactNode;
  /** Right-aligned header slot (e.g. a "Zobraziť všetky" link button). */
  actions?: React.ReactNode;
  /** Body padding. @default "md" */
  padding?: 'none' | 'sm' | 'md' | 'lg';
  style?: React.CSSProperties;
}

/**
 * White rounded-xl surface with hairline border and shadow-sm — the
 * universal Invoicius container for panels, KPI cards and list items.
 */
export function Card(props: CardProps): React.JSX.Element;
