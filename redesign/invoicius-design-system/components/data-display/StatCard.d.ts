import * as React from 'react';

export interface StatCardProps {
  label: React.ReactNode;
  value: React.ReactNode;
  hint?: React.ReactNode;
  /** PrimeIcons class, e.g. "pi-file", "pi-users", "pi-bolt". */
  icon?: string;
  /** Icon-chip accent. @default "sky" */
  accent?: 'sky' | 'violet' | 'amber' | 'emerald';
  style?: React.CSSProperties;
}

/**
 * Dashboard KPI tile with tinted icon chip and tabular value.
 *
 * @startingPoint section="Data" subtitle="Dashboard KPI tiles" viewport="700x150"
 */
export function StatCard(props: StatCardProps): React.JSX.Element;
