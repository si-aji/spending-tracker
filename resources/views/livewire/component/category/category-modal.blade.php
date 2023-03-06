@push('css')
    @include('layouts.plugins.choices-js.css')
@endpush

<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="modal fade" id="category-modal" tabindex="-1" aria-labelledby="category-modalTitle" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <form class="modal-content tw__rounded-lg" wire:submit.prevent="save">
                <div class="modal-header">
                    <h5 class="modal-title" id="category-modalTitle">{{ $category_modalTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group" x-data="{
                        alertState: @entangle('category_uuid')
                    }">
                        <label>Parent</label>
                        <div wire:ignore>
                            <select class="form-control" name="parent_id" id="input_category-parent_id" placeholder="Search Category Parent Data" wire:model.lazy="category_parent">
                                <option value="">Select Category Parent Data</option>
                            </select>
                        </div>
                        <small class=" tw__text-xs tw__italic text-muted" x-show="alertState">*You can change Category Parent on Re-order feature</small>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control @error('category_name') is-invalid @enderror" name="name" id="input_category-name" placeholder="Category Name" wire:model="category_name">
                        @error('category_name')
                            <small class="invalid-feedback tw__block tw__italic tw__text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-check form-switch tw__flex tw__items-center tw__gap-2" x-data="{
                        state: @entangle('category_parent')
                    }">
                        <input class="form-check-input" type="checkbox" id="input_category-keep_parent" wire:model.lazy="category_keepparent" x-bind:disabled="!(state) || !(state.value != '')">
                        <label class="form-check-label tw__m-0" for="input_category-keep_parent">Keep selected parent</label>
                    </div>
                    <div class="form-check form-switch tw__flex tw__items-center tw__gap-2">
                        <input class="form-check-input" type="checkbox" id="input_category-keep_open" wire:model.lazy="category_keepopen">
                        <label class="form-check-label tw__m-0" for="input_category-keep_open">Keep the modal open</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary tw__my-0" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary tw__my-0" id="btn_category-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('javascript')
    @include('layouts.plugins.choices-js.js')

    <script>
        // Modal Show
        window.addEventListener('categoryModal-modalShow', (e) => {
            console.log('Event by Livewire');

            parentChoice.enable();
            if(@this.category_uuid){
                parentChoice.disable();
            }

            let el = document.getElementById('category-modal');
            var myModal = new bootstrap.Modal(el);
            myModal.show();

            el.addEventListener('hidden.bs.modal', (e) => {
                @this.closeModal()
            });
            el.addEventListener('shown.bs.modal', (e) => {
                document.getElementById('input_category-name').focus();
            });
        });
        // Modal Hide
        window.addEventListener('categoryModal-modalHide', (e) => {
            console.log('Event by Livewire / Modal Hide');

            let el = document.getElementById('category-modal');
            if(el.classList.contains('show')){
                console.log('Please hide this modal');

                var myModal = bootstrap.Modal.getInstance(el);
                myModal.hide();
            }
        });
        // Clear Field
        window.addEventListener('categoryModal-clearField', (e) => {
            parentChoice.setChoiceByValue('');
        });

        let parentChoice = null;
        document.addEventListener('DOMContentLoaded', (e) => {
            var element = document.getElementById('input_category-parent_id');
            parentChoice = new Choices(element, {
                allowHTML: true,
                searchPlaceholderValue: 'Search Category Parent Data',
                shouldSort: false
            });
            parentChoice.passedElement.element.addEventListener('showDropdown', (e) => {
                console.log(e);
                parentChoice.clearChoices();

                @this.fetchMainCategory();
                let data = @this.categoryList_data;
                let selectedOption = @this.category_parent;
                let selectOption = [];

                // Placeholder
                if (parentChoice.passedElement.element.length <= 1) {
                    parentChoice.setChoices([{value: '', label: 'Select Category Parent Data', placeholder: true}], 'value', 'label', true);
                }
                // Push data to selection
                data.forEach((val, index) => {
                    selectOption.push({
                        value: val.uuid,
                        label: val.name
                    });
                });

                parentChoice.setValue(selectOption);
                console.log(selectedOption);
                if(selectedOption){
                    parentChoice.setChoiceByValue(selectedOption.value);
                } else {
                    parentChoice.setChoiceByValue('');
                }
            });
            parentChoice.passedElement.element.addEventListener('change', (e) => {
                if(parentChoice.getValue().value === ''){
                    @this.set('category_keepparent', false);
                }
            });
        });
    </script>
@endpush