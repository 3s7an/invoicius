import * as React from 'react';

export interface ButtonProps {
  children?: React.ReactNode;
  /** Visual style. @default "primary" */
  variant?: 'primary' | 'secondary' | 'ghost' | 'danger' | 'text';
  /** @default "md" */
  size?: 'sm' | 'md' | 'lg';
  /** PrimeIcons class, e.g. "pi-plus", "pi-file-pdf". */
  icon?: string;
  /** @default "left" */
  iconPos?: 'left' | 'right';
  loading?: boolean;
  disabled?: boolean;
  fullWidth?: boolean;
  type?: 'button' | 'submit' | 'reset';
  onClick?: (e: React.MouseEvent<HTMLButtonElement>) => void;
  style?: React.CSSProperties;
}

/**
 * Primary action control for Invoicius. Emerald primary matches the
 * PrimeVue Aura buttons used throughout the app.
 *
 * @startingPoint section="Core" subtitle="Emerald button, all variants" viewport="700x200"
 */
export function Button(props: ButtonProps): React.JSX.Element;
