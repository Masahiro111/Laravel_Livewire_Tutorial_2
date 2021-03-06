<div class="p-6">

    <div class="flex items-center justify-end px-4 pb-6 text-right sm:px-4">
        <x-jet-button wire:click="createShowModal">
            {{ __('Create') }}
        </x-jet-button>
    </div>


    {{-- The data table --}}
    <div class="flex flex-col px-4 sm:px-4">
        <div class=" -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Link
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Content
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">

                            @if ($data->count())
                            @foreach ($data as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $item->title }}
                                    {!! $item->is_default_home ? '<span
                                        class="text-green-400 text-xs font-bold">[Default Home]</span>' :
                                    '' !!}
                                    {!! $item->is_default_not_found ? '<span class="text-red-400 text-xs font-bold">[404
                                        page]</span>' :
                                    '' !!}

                                </td>
                                <td class=" px-6 py-4 whitespace-nowrap text-gray-500">
                                    <a href="{{ URL::to('/' . $item->slug)}}" target="_blank"
                                        class="text-blue-500 underline">
                                        {{ $item->slug }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ Str::limit($item->content , 50, '...') }}
                                </td>
                                <td class=" px-6 py-4 whitespace-nowrap text-gray-500 text-right">
                                    <x-jet-button wire:click="updateShowModal({{ $item->id }})">
                                        {{ __('Update') }}
                                    </x-jet-button>
                                    <x-jet-danger-button wire:click="deleteShowModal({{ $item->id }})">
                                        {{ __('Delete') }}
                                    </x-jet-danger-button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class=" px-6 py-4 whitespace-nowrap text-gray-500 text-right" colspan="4">
                                    No Results Found
                                </td>
                            </tr>
                            @endif

                            <!-- More items... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="pt-8">
            {{ $data->links() }}
        </div>
    </div>


    {{-- <!-- Modal Form -- > --}}
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Save Page') }} {{$modelId}}
        </x-slot>

        <x-slot name="content">
            <div class="my-4">
                <x-jet-label for="title" value="{{ __('Title') }}" />
                <x-jet-input id="title" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="title" />
                @error('title')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <input wire:model="title">

            <div class="my-4">
                <x-jet-label for="slug" value="{{ __('Slug') }}" />
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        http://laravel12.localhost/
                    </span>
                    <input type="text" id="slug" wire:model.debounce.800ms="slug"
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm flex-1 block w-full rounded-r-md">
                </div>
                @error('slug')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <input wire:model="slug">

            <div class="mt-4">
                <label>
                    <input class="form-checkbox" type="checkbox" value="{{ $isSetToDefaultHomePage }}"
                        wire:model="isSetToDefaultHomePage">
                    <span class="ml-2 text-sm text-gray-600">Set as the default home page</span>
                </label>
            </div>

            <div class="mt-4">
                <label>
                    <input class="form-checkbox" type="checkbox" value="{{ $isSetToDefaultNotFoundPage }}"
                        wire:model="isSetToDefaultNotFoundPage">
                    <span class="ml-2 text-sm text-red-600">Set as the default 404 page</span>
                </label>
            </div>

            <div class="my-4">
                <x-jet-label for="content" value="{{ __('Content') }}" />
                <textarea wire:model.debounce.800ms="content" id="content" rows="3"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm flex-1 block w-full rounded-r-md "></textarea>
                @error('content')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <input wire:model="content">
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            @if($modelId)
            <x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                {{ __('Update') }}
            </x-jet-button>
            @else
            <x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
                {{ __('Create') }}
            </x-jet-button>
            @endif
        </x-slot>
    </x-jet-dialog-modal>


    <!-- The Delete Modal -->
    <x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">
        <x-slot name="title">
            {{ __('Delete Page') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this page?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>