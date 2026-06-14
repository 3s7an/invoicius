import * as React from 'react';

export interface CheckboxProps {
  checked?: boolean;
  label?: React.ReactNode;
  disabled?: boolean;
  id?: string;
  onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
  style?: React.CSSProperties;
}

/** Emerald checkbox with optional inline label (e.g. "Zapamätať si ma"). */
export function Checkbox(props: CheckboxProps): React.JSX.Element;
