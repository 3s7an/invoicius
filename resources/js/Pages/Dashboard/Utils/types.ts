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

export interface DashboardRecentInvoice {
    id: number
    number: string
    recipient_name: string | null
    total_price: number
    invoice_status: { id: number; code: string; name: string } | null
    created_at: string | null
}

export interface ActiveAutomatization {
    id: number
    type_label: string
    recipient_label: string | null
    date_trigger: string | null
}
