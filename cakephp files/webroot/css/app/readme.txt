Place application-specific CSS files in here.

These files should be named according to the trunk CSS file which they override or extend.
For instance, if your application requires modifications to forms.css which do not belong
in trunk, create webroot/css/app/forms.css and place your overrides in that file.

This way updates to CSS definitions that are global (i.e. which apply to ALL Business 360
instances) will continually be applied to the main CSS files within trunk, then merged
into each of the application branches without conflicting with the applications'
specific CSS definitions, if it has any.