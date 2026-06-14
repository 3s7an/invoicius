import * as React from 'react';

export interface SelectOption { value: string | number; label: string; }

export interface SelectProps {
  label?: React.ReactNode;
  id?: string;
  value?: string | number;
  /** Array of {value,label} or bare strings. */
  options?: Array<SelectOption | string>;
  placeholder?: string;
  disabled?: boolean;
  error?: React.ReactNode;
  hint?: React.ReactNode;
  fullWidth?: boolean;
  onChange?: (e: React.ChangeEvent<HTMLSelectElement>) => void;
  style?: React.CSSProperties;
}

/** Dropdown styled to match PrimeVue Select (used for invoice status, etc.). */
export function Select(props: SelectProps): React.JSX.Element;
