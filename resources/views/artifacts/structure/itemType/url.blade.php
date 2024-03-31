<form>
    <input type="url" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" placeholder="https://www.example.com" {{ ($item->getreadOnly() == "true")?"disabled":"" }}>
</form>
