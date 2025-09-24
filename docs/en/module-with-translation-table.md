# Creating a Module with a Translation Table in Voila CMS

This guide explains how to create a module in **Voila CMS** that is linked with a **translation table**.  
We’ll use the example of building a module called **site_labels**.

---

## 1. Create the Database Tables

You need two tables:  
- **Main Table** (e.g., `site_labels`) → contains static fields that do not change with language.  
- **Translation Table** (e.g., `site_label_translations`) → contains fields that depend on language (multi-language values).  

Make sure to:  
- Add a **foreign key** linking the translation table to the main table.  
- Use a **unique index** on `(locale, foreign_key)` to avoid duplicate translations.  
- Include a `locale` column in the translation table to define the language of each record.

### Table Structures

```sql
DROP TABLE IF EXISTS `site_labels`;
CREATE TABLE IF NOT EXISTS `site_labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `sorting` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

DROP TABLE IF EXISTS `site_label_translations`;
CREATE TABLE IF NOT EXISTS `site_label_translations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label_value` text COLLATE utf8mb4_unicode_ci,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_label_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_label_translations_locale_site_label_id_unique` (`locale`,`site_label_id`) USING BTREE,
  KEY `site_label_translations_site_label_id_foreign` (`site_label_id`),
  KEY `site_label_translations_locale_index` (`locale`)
) ENGINE=MyISAM AUTO_INCREMENT=247 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
---

## 2. Generate the Module from CMS

From the CMS sidebar, navigate to:  
**Module Generator > Add New Module**

You’ll go through **four steps**:

1. **Module Information**:  
   - Choose the base table, translation table, module name, etc.  

2. **Display Fields**:  
   - Select which fields should appear in the listing table.  

3. **Form Fields**:  
   - Select which fields should appear in the add/edit form.  

4. **Module Configuration**:  
   - Enable or disable optional features for the module.  

Once completed, the CMS will automatically generate a link for the new module in the sidebar.

---

## 3. Important Notes

1. Fields that **do not change with language** should remain in the **main table**.  
   Fields that **depend on language** should be stored in the **translation table**.  

2. The `locale` column in the translation table specifies the language of the content.  

3. Always define a proper **foreign key** to link both tables.  

4. Use consistent naming conventions for:  
   - Base table name  
   - Translation table name  
   - Foreign key columns  

5. Behavior inside the CMS:  
   - **Listing Page**: Language-sensitive fields will display based on the Website default language.  
   - **Add/Edit Form**:  
     - Non-language fields appear once (in the default language tab).  
     - Language-dependent fields appear in all language tabs for translation.  

6. To define a field as **translation-enabled**, add the attribute `"translation" => true` in its configuration.

---

## 4. Example Code

Example from `AdminSiteLabelsController.php`:  

### Display Fields
```php
$this->col[] = ["label" => "Label Key", "name" => "label_key", "translation" => false];
$this->col[] = ["label" => "Label Value", "name" => "label_value", "translation" => true];
```
