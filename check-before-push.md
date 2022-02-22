Follow the instruction before push.
- branch format - ```feat/yourname-branch-name```/```fix/yourname-branch-name```
- should define model proper docs.
- extend every model from ```BaseModel```
- extend every policy from ```MasterBasePolicy```. if Module policy then ```BasePolicy```
- extend every controller from ```BaseController```
- is everything translated.
- check policy
    - permission key
    - policy logic
- every view should load in modal except browse, But if view is complex, you can move it to single file.
- Define constant to model if needed, But not use constant for boolean value.
- Use select2
- Use resource api to fetch ajax data.
- route must be named route. definition and implementation.
- if you use date/datetime in db table, then declare casts to model.
```injectablephp
protected $casts = [
    'email_verified_at' => 'datetime',
];
```
