<?php

namespace App\Mappers;

use App\DTOs\CreateInvoiceRecipientData;

final class CreateInvoiceRecipientDataMapper
{
    /**
     * @param array<string, mixed> $data
     */
    public function fromArray(array $data): CreateInvoiceRecipientData
    {
        return new CreateInvoiceRecipientData(
            recipientName: $data['recipient_name'],
            recipientStreet: $data['recipient_street'],
            recipientStreetNum: $data['recipient_street_num'],
            recipientCity: $data['recipient_city'],
            recipientState: $data['recipient_state'],
            recipientIco: $data['recipient_ico'],
            recipientDic: $data['recipient_dic'],
            recipientIcDph: $data['recipient_ic_dph'],
            recipientIban: $data['recipient_iban'],
        );
    }
}
