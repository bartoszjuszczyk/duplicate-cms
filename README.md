# Juszczyk_DuplicateCms module

The Juszczyk_DuplicateCms module enables you to duplicate CMS blocks and CMS pages.

## Installation details

The Juszczyk_DuplicateCms module does not make any changes in database. You can disable or remove this module.

For information about a module installation in Magento 2, see [Enable or disable modules](https://experienceleague.adobe.com/docs/commerce-operations/installation-guide/tutorials/manage-modules.html).

## Usage

### Bulk Duplicate CMS Blocks and Pages:
1. Navigate to the CMS Blocks or CMS Pages listing in the admin panel.
2. Select the blocks/pages you want to duplicate.
3. From the action dropdown, choose "Duplicate."
4. Confirm the action, and the selected blocks/pages will be duplicated.

![Mass action example](https://github.com/bartoszjuszczyk/media/assets/128232518/65b30ca4-256a-4c3c-bb2f-b6f47e4983e9)


### Duplicate from Block/Page Edit View:
1. Open the edit view of the CMS block or page you want to duplicate.
2. In the button bar, find and click the "Duplicate" button.
3. Confirm the action, and a new block/page will be created with the same content.

![Edit form example](https://github.com/bartoszjuszczyk/media/assets/128232518/f90c67eb-5f92-45f8-bfb9-177b54150c2e)


### Duplicate from Block/Page Listing:
1. Go to the CMS Blocks or CMS Pages listing in the admin panel.
2. Find the block/page you want to duplicate in the listing.
3. Locate the "Duplicate" action associated with the specific block/page.
4. Click "Duplicate," confirm the action, and a new block/page will be created.

![Listing actions example](https://github.com/bartoszjuszczyk/media/assets/128232518/d99f9c70-d06c-4d3b-8e7a-14630ffda059)

Remember to review the duplicated content and adjust any specific details if needed. This module streamlines the process for duplicating CMS blocks and pages efficiently.

## Extensibility

Extension developers can interact with the Juszczyk_DuplicateCms module. For more information about the Magento extension mechanism, see [Magento plug-ins](https://developer.adobe.com/commerce/php/development/components/plugins/).

[The Magento dependency injection mechanism](https://developer.adobe.com/commerce/php/development/components/dependency-injection/) enables you to override the functionality of the Juszczyk_DuplicateCms module.

## Dependencies

The Juszczyk_DuplicateCms module extends the functionality of the Magento_Cms module.