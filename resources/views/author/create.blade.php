<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Draft a Story') }}
        </h2>
    </x-slot>

    <!-- Include Quill stylesheet -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>- {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('author.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-gray-500 text-sm font-medium mb-2" for="title">Story Title</label>
                            <input class="w-full text-4xl font-bold border-0 border-b-2 border-gray-100 focus:ring-0 focus:border-gray-900 px-0 py-2 placeholder-gray-300 transition-colors bg-transparent" id="title" type="text" name="title" value="{{ old('title') }}" placeholder="Title" required>
                        </div>

                        <div class="mb-6 flex gap-6">
                            <div class="w-1/2">
                                <label class="block text-gray-500 text-sm font-medium mb-2" for="type">Content Type</label>
                                <select class="w-full border-0 border-b-2 border-gray-100 focus:ring-0 focus:border-gray-900 px-0 py-2 text-gray-900 bg-transparent font-medium" id="type" name="type" required onchange="togglePdfUpload()">
                                    <option value="article" {{ old('type') == 'article' ? 'selected' : '' }}>Article (Text Story)</option>
                                    <option value="ebook" {{ old('type') == 'ebook' ? 'selected' : '' }}>E-Book (PDF Document)</option>
                                </select>
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-500 text-sm font-medium mb-2" for="category_id">Category</label>
                                <select class="w-full border-0 border-b-2 border-gray-100 focus:ring-0 focus:border-gray-900 px-0 py-2 text-gray-700 bg-transparent" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-6 hidden" id="pdf-upload-container">
                            <label class="block text-gray-500 text-sm font-medium mb-2" for="file_upload">Upload E-Book PDF</label>
                            <input class="w-full border border-gray-200 rounded-lg p-2 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" id="file_upload" type="file" name="file_upload" accept="application/pdf">
                            <p class="text-xs text-gray-400 mt-1">Maximum file size: 10MB</p>
                        </div>

                        <div class="mb-8">
                            <label class="block text-gray-500 text-sm font-medium mb-2" for="thumbnail">Cover Image (Optional)</label>
                            <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-200 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition duration-200 overflow-hidden relative" id="preview-container">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center" id="upload-placeholder">
                                    <svg class="w-8 h-8 mb-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> an elegant cover image</p>
                                    <p class="text-xs text-gray-400">PNG, JPG, or GIF (Max 2MB)</p>
                                </div>
                                <img id="thumbnail-preview" class="absolute inset-0 w-full h-full object-cover hidden">
                                <input id="thumbnail" type="file" name="thumbnail" accept="image/*" class="hidden" onchange="previewImage(event)" />
                            </label>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" id="body-label" for="body">Content</label>
                            <!-- Create the editor container -->
                            <div id="editor" class="bg-white" style="height: 300px;">{!! old('body') !!}</div>
                            <input type="hidden" id="body" name="body" value="{{ old('body') }}">
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                            <button class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-sm" type="submit" name="action" value="draft">
                                Save as Draft
                            </button>
                            <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-sm" type="submit" name="action" value="pending">
                                Submit for Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include the Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Write your story here...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        // Sync Quill content to hidden input before submit
        var form = document.querySelector('form');
        form.onsubmit = function() {
            var bodyInput = document.querySelector('input[name=body]');
            bodyInput.value = quill.root.innerHTML;
        };

        // Thumbnail Preview Logic
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('thumbnail-preview');
                output.src = reader.result;
                output.classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('hidden');
            };
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        function togglePdfUpload() {
            var type = document.getElementById('type').value;
            var pdfContainer = document.getElementById('pdf-upload-container');
            var bodyLabel = document.getElementById('body-label');
            
            if (type === 'ebook') {
                pdfContainer.classList.remove('hidden');
                bodyLabel.innerText = 'E-Book Synopsis / Description';
            } else {
                pdfContainer.classList.add('hidden');
                bodyLabel.innerText = 'Article Content';
            }
        }
        
        // Initialize state on load
        togglePdfUpload();
    </script>
</x-app-layout>
