declare namespace App {
namespace DTOs {
namespace Forms {
export type InvoiceFormData = {
readonly number: string,
readonly variable_symbol: string,
readonly issue_date: string,
readonly due_date: string,
readonly currency_id: number,
readonly recipient_id: number | null,
readonly issuer: App.DTOs.Forms.InvoiceIssuerData,
readonly recipient: App.DTOs.Forms.InvoiceRecipientData,
readonly items: App.DTOs.Forms.InvoiceItemData[],
};
export type InvoiceIssuerData = {
readonly name: string | null,
readonly street: string | null,
readonly street_num: string | null,
readonly city: string | null,
readonly zip: string | null,
readonly state: string | null,
readonly ico: string | null,
readonly dic: string | null,
readonly ic_dph: string | null,
};
export type InvoiceItemData = {
readonly name: string,
readonly quantity: number,
readonly unit: string,
readonly unit_price: number,
readonly vat_type_id: number | null,
};
export type InvoiceRecipientData = {
readonly recipient_name: string | null,
readonly recipient_street: string | null,
readonly recipient_street_num: string | null,
readonly recipient_city: string | null,
readonly recipient_state: string | null,
readonly recipient_ico: string | null,
readonly recipient_dic: string | null,
readonly recipient_ic_dph: string | null,
readonly recipient_iban: string | null,
};
}
}
namespace Enums {
export type AutomatizationType = 'invoice_auto_gen' | 'invoice_report' | 'invoice_due_reminder';
export type InvoiceStatusCode = 'draft' | 'sent' | 'paid' | 'overdue';
}
namespace Http {
namespace Resources {
export type CurrencyResource = {
id: number,
name: string,
symbol: string,
};
export type InvoiceItemResource = {
id: number,
name: string,
unit: string,
quantity: number,
unit_price: number,
vat_type_id: number | null,
line_total: number,
};
export type InvoiceResource = {
id: number,
number: string,
variable_symbol: string | null,
recipient_name: string | null,
recipient_street: string | null,
recipient_street_num: string | null,
recipient_city: string | null,
recipient_state: string | null,
recipient_ico: string | null,
recipient_dic: string | null,
recipient_ic_dph: string | null,
iban: string | null,
total_price: number,
issue_date: string | null,
due_date: string | null,
currency_id: number,
recipient_id: number | null,
invoice_status_id: number,
invoice_status: App.Http.Resources.InvoiceStatusResource | null,
items: App.Http.Resources.InvoiceItemResource[] | null,
created_at: string | null,
};
export type InvoiceStatusResource = {
id: number,
code: string,
name: string,
};
export type RecipientResource = {
id: number,
name: string | null,
company_name: string | null,
street: string | null,
street_num: string | null,
city: string | null,
zip: string | null,
state: string | null,
ico: string | null,
dic: string | null,
ic_dph: string | null,
iban: string | null,
};
export type VatTypeResource = {
id: number,
code: string,
rate: number,
};
}
}
}
