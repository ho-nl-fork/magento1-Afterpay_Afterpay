Name: AfterPay Magento Extension
Version: 1.5.4

Information for installing or support can be found at http://integration.afterpay.nl, or at support@afterpay.nl

Release notes, version 1.5.4:
- Fixed problem with not supported array_search php function
- Fixed enterprise logic which causes double service fee in Enterprise environments
- Fixed enterprise check which causes problems with sending order mails

Release notes, version 1.5.3:
- Fixes: Fixed problem with template file showing portfolio information
- Fixes: Fixed problem showing the correct vat amount on the service fee in the backend on orders, invoices, credit notes and pdf
- Fixes: Fixed posibility for using the adjustment fees when refunding in Magento
- Fixes: Fee not showing in One Step Checkout, when AfterPay is selected, but shipping is not.
- Removals: Removed AfterPay logo, supporttab and coloring information in adminhtml
- Additions: Created extensive support for bundled products with flexible pricing. Send in detail with the correct vat category

Release notes, version 1.5.2:
- Changes: Compatible with Magento 1.9.2.2
- Changes: Compatible with IWD Onestepcheckout 4.08
- Fixes: Fixed vat category on refund with discount 
- Fixes: Fixed view in IDEV Onestepcheckout (including vat and after shipping)
- Additions: The following order line is only showed when the order contains discount: ‘De stuksprijs is incl. eventuele korting’
- Fixes: Fixed double order confirmation mails
- Fixes: Fixed calculation on service fee when percentage is used
- Additions: Possibility to add status to failed captures
- Fixes: support for extra refund fields (refund amount and refund fee)
- Fixes: Compatibility increase with the PostNL and Buckaroo Module
- Fixes: several small bugfixes and language improvements

Release notes, version 1.4.0:
- Changes: Compatible with Magento 1.9.2.0
- Changes: Compatible with Magento Security Update (PATCH_SUPEE-6285_CE_1.9.1.1_v1 en PATCH_SUPEE-6285_CE_1.9.1.1_v2)
- Changes: Compatible with IWD Onestepcheckout 4.08
- Changes: Compatible with TIG_PostNL Module (probleem with service fee)
- Changes: Structural naming change (TIG_Afterpay now Afterpay_Afterpay).
- Changes: Removed non-risk posibility.
- Fixes: Improvement in testing with IP restriction
- Changes: SOAP endpoint changed from api.afterpay.nl to mijn.afterpay.nl
- Addition: New trademarks added to back and frontend
- Fixes: Fix for order confirmation mail from version 1.9.1.0.
- Changes: Merchant ID on portefolio setting instead of general settings
- Changes: Modus (test/live) only on portfolio setting
- Fixes: Refund on alternative merchant ID
- Changes: Improvement in sending IP adresses for loadbalancers and Cloudflare environments
- Fixes: Servicekosten view improvement 
- Fixes: several small bugfixes and language improvements