<form>
    <input type="text" placeholder="Insira seu texto aqui" {{ ($item->getreadOnly() == "true")?"disabled":"" }}>
</form>
