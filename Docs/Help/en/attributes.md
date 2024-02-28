# Attributes

## Default

The module automatically installs the following default attributes which can be set in the attribute tab in the respective client.

### General

| Attribute | Description | Internal default value |
| --------- | ----------- | ---------------------- |
| abc_class | Custom client rating | |
| support_emails | Send email for support ticket changes | yes |
| support_email_address | Email address for support tickets | Account email |
| legal_form | Client legal form | |

### Categories

Clients can be put in categories for horizontal and vertical grouping. By default the system uses segment->section->client_group as categories as well as client_type. These categories also get used by other modules. Additional groups can be defined but are not used by other modules by default.

| Attribute | Description | Internal default value |
| --------- | ----------- | ---------------------- |
| segment | Level 1 | 1 |
| section | Level 2 | 1 |
| client_group | Level 3 | 1 |
| client_type | **NOT** hierarchically. | 1 |
| client_area | **NOT** hierarchically. Area a client belongs to. Useful for grouping customers based on location or sales rep. | |

| Level | >                   | >   | >                   | >   | >                   | >                   | Sample             |
| :---: | :-----------------: | :-: | :-----------------: | :-: | :-----------------: | :-----------------: | :----------------: |
| 1     | >                   | >   | >                   | >   | Segment 1           | >                   | Segment 2          |
| 2     | >                   | >   | Section 1.1         | >   | Section 1.2         | >                   | Section 2.1        |
| 3     | Client Group 1.1.1  | >   | Client Group 1.1.2  | >   | Client Group 1.2.1  | Client Group 2.1.1  | Client Group 2.1.2 |

> You could consider the client (number) itself `Level 4`.

### Billing

| Attribute | Description | Internal default value |
| --------- | ----------- | ---------------------- |
| bill_emails | Should bills get emailed to the customer | yes |
| bill_email_address | Email address used for sending bill via email | account email |
| bill_language | Language of the bill | Account language -> default bill language |
| bill_currency | Currency of the bill. Coming soon. | |

### Purchase & Stock

| Attribute | Description | Internal default value |
| --------- | ----------- | ---------------------- |
| minimum_order | Minimum order amount required from customer | |

### Accounting

| Attribute | Description | Internal default value |
| --------- | ----------- | ---------------------- |
| sales_tax_code | Tax code for sales | |
| vat_id | VAT id for european customers | |
| tax_id | Tax id for local tax id | |
| line_of_credit | Maximum amount allowed to be purchased taking unpaid invoices into account | |
| credit_rating | Credit rating | |