<form>
        <label>
            <input type="radio" name="{{$item->getLinkId()}}" id="{{$item->getLinkId()}}" value="true" {{ ($item->getreadOnly() == "true")?"disabled":"" }}> Yes
        </label>
        <label>
            <input type="radio" name="boolean" value="false" {{ ($item->getreadOnly() == "true")?"disabled":"" }}> No
        </label>
</form>
