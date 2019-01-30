<form method="post" action="{{ url('test_image') }}">
    @csrf
        <input type="file" name="image">
<input type="submit">
</form>