# Backend Feature Implementation Skill

## Purpose

Implement backend logic following Laravel architecture rules: thin controllers, business logic in services, validation in Form Requests.

---

## When to Use

- Building backend feature logic
- Creating new API endpoints
- Implementing business operations
- Adding new service methods

---

## Process

### Step 1: Validation (Form Request)

- Create Form Request in `app/Http/Requests/`
- Define all validation rules
- Handle custom validation logic
- **Never validate in controller**

### Step 2: Service Layer

- Create or extend Service in `app/Services/Admin/` or `app/Services/Api/`
- Implement business logic
- Keep service methods focused and single-purpose
- Use `CrudBaseService` for CRUD operations
- **All business logic goes here**

### Step 3: Controller (Thin)

- Receive Form Request
- Call Service method(s)
- Return response (view or JSON)
- **No business logic in controller**
- **No validation in controller**
- **Only orchestration**

### Step 4: Model Interaction

- Use models for data operations
- Define relationships in models
- Use scopes for common queries
- Avoid raw queries when Eloquent works

### Step 5: Error Handling

- Throw exceptions for errors
- Use custom exception classes
- Catch and transform in controller
- Return consistent error responses

---

## Project Service Structure

### CRUD Services
```
app/Services/Admin/Base/CrudBaseService.php
app/Services/Admin/{Module}Service.php  // extends CrudBaseService
```

### Auth Services
```
app/Services/Admin/Auth/LoginService.php
app/Services/Auth/AuthService.php
```

### Export Services
```
app/Services/Admin/Export/ExportService.php
app/Services/Admin/Export/Strategies/ExcelExporter.php
```

---

## Validation Pattern

### Form Request
```php
// app/Http/Requests/StorePostRequest.php
class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
        ];
    }
}
```

### Controller Usage
```php
public function store(StorePostRequest $request)
{
    $data = $request->validated();
    $post = $this->service->create($data);
    
    return redirect()->route('admin.posts.index');
}
```

---

## Service Pattern

```php
// app/Services/Admin/PostService.php
class PostService extends CrudBaseService
{
    public function create(array $data): Post
    {
        // Business logic here
        return Post::create($data);
    }

    public function update(Post $post, array $data): Post
    {
        // Business logic here
        $post->update($data);
        return $post->fresh();
    }

    public function delete(Post $post): void
    {
        // Business logic here
        $post->delete();
    }
}
```

---

## Error Handling

### Throwing Exceptions
```php
throw new \App\Exceptions\ServiceException('Error message', 422);
```

### Catching in Controller
```php
try {
    $this->service->performAction($data);
} catch (ServiceException $e) {
    return back()->withErrors($e->getMessage());
}
```

---

## Model Usage

### Relationships
```php
// In Post model
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}

public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class);
}
```

### Scopes
```php
// In Post model
public function scopeActive($query)
{
    return $query->where('is_active', true);
}
```

---

## Rules Enforcement

| Layer | Responsibility |
|-------|----------------|
| Controller | Orchestration only |
| Form Request | Validation |
| Service | Business logic |
| Model | Data access & relationships |

---

## Common Patterns

### Index with Filters
```php
// Service
public function getFiltered(array $filters): LengthAwarePaginator
{
    $query = Post::query();
    
    if (!empty($filters['search'])) {
        $query->where('title', 'like', "%{$filters['search']}%");
    }
    
    if (!empty($filters['category_id'])) {
        $query->where('category_id', $filters['category_id']);
    }
    
    return $query->paginate(15);
}

// Controller
public function index(PostIndexRequest $request)
{
    $posts = $this->service->getFiltered($request->validated());
    return view('admin.posts.index', compact('posts'));
}
```

### Store with Relations
```php
// Service
public function create(array $data): Post
{
    $post = Post::create($data);
    
    if (!empty($data['tags'])) {
        $post->tags()->sync($data['tags']);
    }
    
    return $post;
}
```

---

## Completion Standard

Backend implementation is NOT complete until:

- [ ] Validation in Form Request (not controller)
- [ ] Business logic in Service (not controller)
- [ ] Controller is thin (only orchestration)
- [ ] Model relationships defined
- [ ] Error handling in place
- [ ] Follows project patterns (CrudBaseService, etc.)
- [ ] Tests added for service layer

---

## Output Format

- Form Request classes
- Service methods
- Controller methods
- Model changes
- Error handling approach
- Any patterns to follow