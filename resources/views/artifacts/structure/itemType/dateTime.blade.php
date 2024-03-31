<form>
    <input type="datetime-local" {{ ($item->getreadOnly() == "true")?"disabled":"" }} name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}">
</form>
