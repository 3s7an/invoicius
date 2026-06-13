export interface AutomatizationTypeOption {
    value: string
    label: string
    requires_recipient: boolean
    requires_due_offset_days: boolean
    requires_item_names: boolean
    uses_daily_schedule: boolean
}

export interface Automatization {
    id: number
    type: App.Enums.AutomatizationType
    type_label: string
    recipient_id: number | null
    recipient_label: string | null
    date_trigger: string | null
    due_offset_days: number | string | null
    is_active: boolean
    item_names: string[]
}
