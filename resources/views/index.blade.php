@extends('layouts.app')

@section('title')
{{ __('Data Providers List') }}
@endsection

@section('link')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container-fluid">
  <div class="card" style="margin:40px;">
    <div class="card-header">
      <h1>Data Providers</h1>
    </div>
    <div class="card-body">
      <h5 class="card-title">Add new data providers</h5>
      <form class="form-inline text-center" id="add-data-provider-form" onsubmit="return false;">
        <label for="name">Name:</label>
        <input type="text" id="name" placeholder="Enter name" name="name">
        <label for="url">URL:</label>
        <input type="text" id="url" placeholder="Enter URL" name="url">
        <button class="btn btn-primary" id="add-data-provider">Submit</button>
      </form>
      <br>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">URL</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($dataProviders as $dataProvider)
          <tr>
            <th scope="row">{{ $dataProvider->id }}</th>
            <td id="data-provider-name-{{ $dataProvider->id }}">{{ $dataProvider->name }}</td>
            <td id="data-provider-url-{{ $dataProvider->id }}">{{ $dataProvider->url }}</td>
            <td>
              <button
                type="button"
                class="btn btn-info image-data-provider"
                data-show-image-url="{{ route('data-provider.show', $dataProvider->id) }}">
                Image
              </button>
              <button
                class="btn btn-primary edit-data-provider"
                data-bs-toggle="modal"
                data-bs-target="#edit-modal"
                data-provider-name="{{ $dataProvider->name }}"
                data-provider-url="{{ $dataProvider->url }}"
                data-edit-url="{{ route('data-provider.update', $dataProvider->id) }}">Edit</button>
              <button
                class="btn btn-danger delete-data-provider"
                data-bs-toggle="modal"
                data-bs-target="#delete-modal"
                data-delete-url="{{ route('data-provider.delete', $dataProvider->id) }}">
                Delete
              </button>
            </td>
          </tr>
          @empty
          <tr>
              <td bgcolor="#FFFFFF" colspan="4">&nbsp;</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      <div class="float-right">
          {{ $dataProviders->links() }}
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="image-modal" tabindex="-1" aria-labelledby="image-modal-label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="image-modal-label">Random Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <img
            id="random-image"
            src=""
            style="display: block;margin-left: auto;margin-right: auto;width: 60%;height: 40%;">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="edit-modal-label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="edit-modal-label">Edit Data Provider</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="text-center" id="edit-data-provider-form" onsubmit="return false;">
          <div class="modal-body">
              @method('PUT')
              <label for="name">Name:</label>
              <input type="text" id="edit-name" placeholder="Enter name" name="name">
              <label for="url">URL:</label>
              <input type="text" id="edit-url" placeholder="Enter URL" name="url">
            </div>
            <div class="modal-footer">
              <button
                id="close-edit-modal-button"
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal">
                Cancel
              </button>
              <button
                id="confirm-edit-data-provider"
                type="button"
                class="btn btn-primary">
                Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-modal-label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="delete-modal-label">Delete Data Provider</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Delete data provider?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button
            id="confirm-delete-data-provider"
            type="button"
            class="btn btn-danger"
            data-bs-dismiss="modal">
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="module">
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Add data providers
  $("#add-data-provider").click(function(e) {
    let form_data = new FormData();

    $('#add-data-provider-form')
      .serializeArray()
      .forEach((field) => {
        form_data.append(field['name'], field['value'])
      })

    $.ajax({
        type: 'POST',
        url: "{{ route('data-provider.create') }}",
        data: form_data,
        scriptCharset: 'sjis-win',
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(data){
          if($.isEmptyObject(data.error)){
            alert(data.body.message);
            location.reload();
          }
        },
        error: function(data) {
          let response = JSON.parse(data.responseText)
          alert(response.body.message.join('\n'));
        }
    });
  });

  // Display random images
  $(".image-data-provider").click(function(e) {
    // Clear image
    $('#random-image').attr('src', '');

    // Request random image
    $.ajax({
      type: 'GET',
      url: $(this).attr('data-show-image-url'),
      success: function(data){
        if($.isEmptyObject(data.error)){
          alert(data.body.message);
          // Update image in modal
          $('#random-image').attr('src', data.body.data.message);
          let myModal = new bootstrap.Modal(document.getElementById('image-modal'), {})
          myModal.show();
        }
      },
      error: function(data) {
        let response = JSON.parse(data.responseText)
        alert(response.body.message.join('\n'));
      }
    });
  });

  // Edit data providers
  $(".edit-data-provider").click(function(e) {
    // Update values in form
    $('#edit-name').val($(this).attr('data-provider-name'));
    $('#edit-url').val($(this).attr('data-provider-url'));
    // Add url to save button
    $('#confirm-edit-data-provider').attr('data-edit-url', $(this).attr('data-edit-url'));
  });

  $("#confirm-edit-data-provider").click(function(e) {
    let form_data = new FormData();

    $('#edit-data-provider-form')
      .serializeArray()
      .forEach((field) => {
        form_data.append(field['name'], field['value'])
      })

    $.ajax({
      type: 'POST',
      url: $(this).attr('data-edit-url'),
      data: form_data,
      scriptCharset: 'sjis-win',
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function(data){
        if($.isEmptyObject(data.error)){
          alert(data.body.message);
          // Update text in list
          $('#data-provider-name-' + data.body.data.id).text(data.body.data.name);
          $('#data-provider-url-' + data.body.data.id).text(data.body.data.url);
          // Hide modal
          $('#close-edit-modal-button').trigger('click');
        }
      },
      error: function(data) {
        let response = JSON.parse(data.responseText)
        alert(response.body.message.join('\n'));
      }
    });
  });

  // Delete data providers
  $('.delete-data-provider').click(function(e) {
    // Add url to delete button
    $('#confirm-delete-data-provider').attr('data-delete-url', $(this).attr('data-delete-url'));
  });

  $('#confirm-delete-data-provider').click(function(e) {
    $.ajax({
      type: 'DELETE',
      url: $(this).attr('data-delete-url'),
      scriptCharset: 'sjis-win',
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function(data){
        if($.isEmptyObject(data.error)) {
          alert(data.body.message)
          location.reload()
        }
      },
      error: function(data) {
        let response = JSON.parse(data.responseText)
        alert(response.body.message.join('\n'))
      }
    });
  });


</script>
@endsection