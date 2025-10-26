# Using Content Builder in Voila CMS

This documentation explains how to integrate and enable the Content Builder for editable content fields within Voila CMS modules, such as **Services**.

---

## ✅ Step 1: Update the Admin Services Controller

Enable the `ContentBuilderTrait` and generate Content Builder action buttons.

```php
use App\Traits\ContentBuilderTrait; // Added code

class AdminServicesController extends CBController
{
    use ContentBuilderTrait; // Added code

    public function cbInit()
    {
        // Add Content Builder action buttons for the content field
        $this->addaction = array();
        $this->generateContentBuilderActionBtns($this->addaction, 'content', 'services/[slug]'); // Added code
    }
}
```

### Important Notes

1. Ensure the `content` field exists in `$this->form[]` with:
   ```php
   'type' => 'hidden'
   ```
2. If your module uses a translation table, then `generateContentBuilderActionBtns()` will automatically add Content Builder buttons for translated fields.
3. If the module does not use translation, you must manually add action buttons to your field.
```php
    $this->addaction[] = [
					'label'  => "Edit Content",
					'title'  => "Edit Content",
					'target' => '_blank',
					'url'    => CRUDBooster::mainpath('content-builder-iframe') . "/[id]?field=content&lang=ar&url=blog\[id]",
					'icon'   => 'fa fa-wrench'
				];
```

---

## ✅ Step 2: Update the Frontend View Controller

Use the trait in your front controller to regenerate formatted content at runtime.

```php
use App\Traits\ContentBuilderTrait; // Added code

class ServicesController extends Controller
{
    use ContentBuilderTrait; // Added code

    public function details($slug)
    {
        $service = Service::where('slug', $slug)->first();

        // Regenerate Content Builder HTML Output
        $service = $this->reGenerateContentBuilderCode($service, 'content'); // Added code

        return view("pages.services.details", compact(
            'service',
            'faqs',
            'galleries',
            'testimonials',
            'subServices'
        ));
    }
}
```

---

## 🎨 Step 3: Match the Content Builder with Your Website Styles

The default tools must be styled to visually match your frontend.

### 1️⃣ Modify Builder JS Configuration

Edit:
`public/content_builder/js/builder.js`

Tasks:

- Update main brand colors in variables
- Add your website's CSS links inside `grapesjs.init` under: canvas => styles
  Look for the comment:
  ```
  /* Please adjust the following styles to match your website's style. */
  ```
  and replace URLs below it with your own style files.

---

### 2️⃣ Update the Builder Color Styles

Edit:
`public/content_builder/css/colors.css`

- Set builder colors to match your website theme
- Include both:
  ```
  colors.css
  canvas.css
  ```
  in your frontend layout `<head>`

---

### 3️⃣ Add Custom Content Blocks

Insert your blocks into the `custom_blocks` database table.

- Make sure `builder_type = "content_builder"`

This allows custom blocks to appear directly in the builder interface.

---

## 📂 File Structure Reference

Below are the core Content Builder files and directories used by Voila CMS:

```
App/Traits/ContentBuilderTrait.php
App/Models/Language/Language.php
public/content_builder/
resources/views/content_builder/
```

---

## ✅ Conclusion

Once the above steps are applied:

✔ You will have fully editable content using Content Builder  
✔ Styling will match your website perfectly  
✔ Custom blocks can be expanded as needed  

Enjoy crafting rich, dynamic content without touching your database fields manually anymore!

---


