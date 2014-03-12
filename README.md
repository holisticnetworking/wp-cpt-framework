# WP CPT Framework

An easy startup for creating WordPress Custom Post Types with taxonomies and custom fields.

## FYI: This is Not a Plugin!

The WP CPT Framework really just works as a template for creating your own Custom Post Types the old fashioned way. You can install it into your /plugins folder and you can turn it on and it will work. But unless you want a CPT called "headlines," you're going to need to do some of your own coding.

### Contained in this framework:
- A main controller
- An example CPT object class
- A sample archive and single post template

### The Controller
The controller governs a number of functions. One at a time, they are:
- **admin_pages**: Adds the Options page that allows you to select and deselect post types as required. Additionally allows the hiding of Post and Page types if you've deselected them in the menu.
- **content_types**: The menu and saving function for registering content types
- **register_content_types**: Definitely have a look at this! Dynamically searches our saved post_types site variable, checks the ./cpt folder for the correct file, and instantiates the CPT. Also contains logic to turn off Post and Page types, but in debug mode, will cause errors. This logic is commented out, use at your own risk!
- **unregister_type**: Self explanatory, in the case of Post and Page types, this function unregisters those types if unselected and the above logic is uncommented.
- **template**: Allows you to use default post type templates stored in the ./tpl directory. Will be overriden by a theme file of the same name.
- **get_post_types**: Gets the list of Post Types currently registered, minus Post and Page, which need to be handled separately. Also creates regex-compatible names for searching directories.
- **flush_rewrite**: Doesn't seem to work well, but when does flushing rewrites ever work well in WP? You're probably going to need to flush by changing the permalink structure breifly and back again. Limits of the WP framework, but I'm open to suggestions?
- **WPCustomPostType**: Our constructor function.

### The CPT File
We use this function to provide all the information WordPress requires to create a CPT, including registering any meta values and their corresponding fields and saving functions. All of this functionality is well documented on the WP Codex and probably doesn't bear reiteration here.
