<?php

namespace VCComponent\Laravel\Contact\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\Contact\Entities\Contact;
use VCComponent\Laravel\Contact\Repositories\ContactRepository;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;
use VCComponent\Laravel\Contact\Traits\Helpers;
/**
 * Class AccountantRepositoryEloquent.
 */
class ContactRepositoryEloquent extends BaseRepository implements ContactRepository
{
    use Helpers;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        if (isset(config('contact.models')['contact'])) {
            return config('contact.models.contact');
        } else {
            return Contact::class;
        }
    }

    public function getEntity()
    {
        return $this->model;
    }

    public function updateStatus($request, $id)
    {
        $contact = $this->find($id);
        $contact->status = $request->input('status');
        $contact->save();
    }

    public function bulkUpdateStatus($request)
    {

        $data     = $request->all();
        $contacts = $this->findWhereIn("id", $request->ids);

        if (count($request->ids) > $contacts->count()) {
            throw new NotFoundException("contacts");
        }

        $result = $this->whereIn("id", $request->ids)->update(['status' => $data['status']]);

        return $result;
    }

    public function createContact($request)
    {
        $data = $this->filterContactRequestData($request,$this->getEntity());
        $contact = $this->create($data['default']);
        if (count($data['schema'])) {
            foreach ($data['schema'] as $key => $value) {
                $contact->metaContact()->updateOrcreate([
                    'key' => $key,
                ], [
                    'value' => $value,
                ]);
            }
        }
        return true;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
