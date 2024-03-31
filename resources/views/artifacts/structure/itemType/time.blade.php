<form>
    <input type="time" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}"{{ ($item->getreadOnly() == "true")?"disabled":"" }}>
</form>