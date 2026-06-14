import * as React from 'react';

export interface InputProps {
  label?: React.ReactNode;
  id?: string;
  type?: string;
  value?: string;
  placeholder?: string;
  /** Helper text below the field. */
  hint?: React.ReactNode;
  /** Error message; turns the border red and replaces the hint. */
  error?: React.ReactNode;
  required?: boolean;
  disabled?: boolean;
  /** Leading PrimeIcons class, e.g. "pi-envelope". */
  icon?: string;
  fullWidth?: boolean;
  onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
  style?: React.CSSProperties;
}

/** Labelled text field with hint/error states and emerald focus ring. */
export function Input(props: InputProps): React.JSX.Element;
