# Project rules


## Rules for document requirements

- All `functional requirements` should be present in at least:
    - integration test, that proves the requirement was met
    - the public methods invoked to have the requirement met

- All public methods should have in their documentation, a list of `functional requirements` met on that.
    - If any public method does not have any requirement linked to it, then maybe there is a requirement missing, or may be necessary document that more.
    - If an internal method is used as helper for any other, and don't fulfill directly any specific requirement, then the method probably should have the visibility protected or private.
