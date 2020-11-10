<?php

namespace VCComponent\Laravel\Contact\Http\Controllers\Api\Frontend;

use Illuminate\Http\Request;
use VCComponent\Laravel\Contact\Repositories\ContactRepository;
use VCComponent\Laravel\Contact\Traits\Helpers;
use VCComponent\Laravel\Contact\Transformers\ContactTransformer;
use VCComponent\Laravel\Contact\Validators\ContactValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

class ContactController extends ApiController
{
    use Helpers;

    protected $repository;
    protected $entity;
    protected $transformer;
    protected $validator;

    public function __construct(ContactRepository $repository, ContactValidator $validator)
    {
        $this->repository = $repository;
        $this->entity = $repository->getEntity();
        $this->validator = $validator;

        if (isset(config('contact.transformers')['contact'])) {
            $this->transformer = config('contact.transformers.contact');
        } else {
            $this->transformer = ContactTransformer::class;
        }

        if (!empty(config('contact.auth_middleware.frontend'))) {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToUse($user)) {
                throw new PermissionDeniedException();
            }

            foreach (config('contact.auth_middleware.frontend') as $middleware) {
                $this->middleware($middleware['middleware'], ['except' => $middleware['except']]);
            }
        }
    }

    public function index(Request $request)
    {
        $query = $this->entity->query();
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['email', 'full_name', 'first_name', 'last_name'], $request, ['metaContact' => ['value']]);
        $query = $this->applyOrderByFromRequest($query, $request);

        $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $contacts = $query->paginate($per_page);

        return $this->response->paginator($contacts, new $this->transformer());
    }

    function list(Request $request) {
        $query = $this->entity->query();

        $query = $this->applyConstraintsFromRequest($query, $request);

        $query = $this->applySearchFromRequest($query, ['email', 'full_name', 'first_name', 'last_name'], $request, ['metaContact' => ['value']]);

        $query = $this->applyOrderByFromRequest($query, $request);

        $contacts = $query->get();

        return $this->response->paginator($contacts, new $this->transformer());
    }

    public function show(Request $request, $id)
    {
        $contact = $this->repository->find($id);

        return $this->response->item($contact, new $this->transformer());
    }

    public function store(Request $request)
    {

        $data = $this->filterContactRequestData($request, $this->entity);

        $schema_rules = $this->validator->getSchemaRules($this->entity);
        $no_rule_fields = $this->validator->getNoRuleFields($this->entity);

        $this->validator->isValid($data['default'], 'RULE_CREATE');
        $this->validator->isSchemaValid($data['schema'], $schema_rules);

        $contact = $this->repository->create($data['default']);

        if (count($data['schema'])) {
            foreach ($data['schema'] as $key => $value) {
                $contact->metaContact()->updateOrcreate([
                    'key' => $key,
                ], [
                    'value' => $value,
                ]);
            }
        }
        return $this->response->item($contact, new $this->transformer());
    }
}
