<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class JobController extends Controller
{
    use AuthorizesRequests;

    //@desc show all job listings
    //@route GET /jobs
    public function index(): View
    {
        /*for session test
         $value = session()->get('test');
        dd($value);
        */
        $jobs = Job::latest()->paginate(9);
        // return view('jobs.index', compact('jobs'));
        return view('jobs.index')->with('jobs', $jobs);
    }

    //@desc show create job form
    //@route GET /jobs/create
    public function create()
    {
        // if (!Auth::check()) {
        //     return redirect()->route('login');
        // }
        return view('jobs.create');
    }

    //@desc Save job to database
    //@route POST /jobs/create
    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());
        // dd($request->file('company_logo'));
        $validatedData = $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'salary' => 'required|integer',
                'tags' => 'nullable|string',
                'job_type' => 'required|string',
                'remote' => 'required|boolean',
                'requirements' => 'nullable|string',
                'benefits' => 'nullable|string',
                'address' => 'nullable|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'contact_email' => 'required|string',
                'contact_phone' => 'nullable|string',
                'company_name' => 'required|string',
                'company_description' => 'nullable|string',
                'company_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
                'company_website' => 'nullable|url',
            ]
        );

        //Hard coded user ID
        $validatedData['user_id'] = auth()->user()->id;

        // Check for image
        if ($request->hasFile('company_logo')) {
            // Store the file and get path
            $path = $request->file('company_logo')->store('logos', 'public');

            // Add path to validated data
            $validatedData['company_logo'] = $path;
        }

        // Submit to database
        Job::create($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job listing created successfully!');
    }

    //@desc Display a single job listings
    //@route GET /jobs/{$id}
    public function show(Job $job): View
    {
        return view('jobs.show')->with('job', $job);
    }

    //@desc show edit job form
    //@route GET /jobs/{$id}/edit
    public function edit(Job $job): View
    {
        //check if user is authorized
        $this->authorize('update', $job);

        return view('jobs.edit')->with('job', $job);
    }

    //@desc Update job listings
    //@route PUT /jobs/{$id}
    public function update(Request $request, Job $job): string
    {

        //check if user is authorized
        $this->authorize('update', $job);

        $validatedData = $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'salary' => 'required|integer',
                'tags' => 'nullable|string',
                'job_type' => 'required|string',
                'remote' => 'required|boolean',
                'requirements' => 'nullable|string',
                'benefits' => 'nullable|string',
                'address' => 'nullable|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'contact_email' => 'required|string',
                'contact_phone' => 'nullable|string',
                'company_name' => 'required|string',
                'company_description' => 'nullable|string',
                'company_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
                'company_website' => 'nullable|url',
            ]
        );

        // Check for image
        if ($request->hasFile('company_logo')) {

            //Delete old logo
            Storage::delete('public/logos/' . basename($job->company_logo));

            // Store the file and get path
            $path = $request->file('company_logo')->store('logos', 'public');

            // Add path to validated data
            $validatedData['company_logo'] = $path;
        }

        // Submit to database
        $job->update($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job listing updated successfully!');
    }

    //@desc Delete a job listing
    //@route DELETE /jobs/{$id}
    public function destroy(Job $job): RedirectResponse
    {
        //check if user is authorized
        $this->authorize('delete', $job);

        //If logo , the delete it

        if ($job->company_logo) {
            Storage::delete('public/logos/' . $job->company_logo);
        }
        $job->delete();

        //check if request came from dashboard

        if (request()->query('from') === 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Job listing deleted successfully!');
        }

        return redirect()->route('jobs.index')->with('success', 'Job listing deleted successfully!');
    }


    //@desc Search  job listing
    //@route GET /jobs/search
    public function search(Request $request): View
    {
        $keywords = strtolower($request->input('keywords'));
        $location = strtolower($request->input('location'));

        // dd($keywords, $location);

        $query = Job::query();

        if ($keywords) {
            $query->where(function ($q) use ($keywords) {
                $q->whereRaw('LOWER(title) like ?', ['%' . $keywords . '%'])
                    ->orWhereRaw('LOWER(description) like ?',  ['%' . $keywords . '%'])
                    ->orWhereRaw('LOWER(tags) like ?',  ['%' . $keywords . '%']);
            });
        }

        if ($location) {
            $query->where(function ($q) use ($location) {
                $q->whereRaw('LOWER(address) like ?', ['%' . $location . '%'])
                    ->orWhereRaw('LOWER(city) like ?',  ['%' . $location . '%'])
                    ->orWhereRaw('LOWER(state) like ?',  ['%' . $location . '%'])
                    ->orWhereRaw('LOWER(zipcode) like ?',  ['%' . $location . '%']);
            });
        }

        $jobs = $query->paginate(12);
        return view('jobs.index')->with('jobs', $jobs);
    }
}
