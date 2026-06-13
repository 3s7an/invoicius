export interface InvoiceStats {
    total_invoiced: number
    paid: number
    awaiting: number
    overdue: number
    draft: number
}

export interface DashboardCounts {
    invoices: number
    clients: number
    automatizations_active: number
}

export interface ActiveAutomatization {
    id: number
    type_label: string
    recipient_label: string | null
    date_trigger: string | null
}
