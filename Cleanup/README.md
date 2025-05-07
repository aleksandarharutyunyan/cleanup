# Magento 2 Cleanup Module

This module provides various cleanup utilities for Magento 2, including:

✅ Deletion of obsolete attributes and CMS blocks  
✅ Cleanup of unused website/store data  
✅ Removal of legacy or demo integration artifacts  
✅ CLI commands for structured data cleanup

---

## ⚠️ Warning

This module **modifies and deletes real database data**.  
Use only in **development** or **staging** environments, unless you know exactly what you're doing.

---

## 🔧 Features

### Setup Data Patches (`Setup/Patch/Data`)
- `DeleteCustomLinkTarget` – removes a custom category attribute
- `DeleteLastBikeConfigAttribute` – deletes a specific product attribute
- `DeleteOrderArchiveData` – cleans custom archived order data
- `DeleteTimeWebsite` – removes website/store entities by code
- `RemoveNotLinkedToStoresBlocks` – deletes CMS blocks not linked to any store
- `DeleteSynoliaAutopopupData` – (rename recommended) cleans table `synolia_autopopup`

### Console Commands (`Console/Command`)
- `DeleteTaxClassCode` – deletes tax classes by code
- `DeleteWebsiteGroupData` – removes store groups with no associated stores

---

## 🚀 Installation

Place the module in `app/code/Aiops/Cleanup`:

```
bin/magento module:enable Aiops_Cleanup
bin/magento setup:upgrade
bin/magento cache:flush
```

---

## 💻 Usage – Console

Run available commands:

```
bin/magento list aiops
```

Example:

```
bin/magento aiops:cleanup:delete-tax-classes
```

---

## 💡 Notes

- Designed for internal use and project-specific cleanups
- Use class structure to extend with your own cleanup logic
- Consider adding dry-run mode for commands if used on live data

---

## 📘 License

MIT – Use freely, modify responsibly.
