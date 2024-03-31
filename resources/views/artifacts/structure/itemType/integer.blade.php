<form>
    <input type="number" placeholder="Enter your number here" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" {{ ($item->getreadOnly() == "true")?"disabled":"" }}>
</form>
