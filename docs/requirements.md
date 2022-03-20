# Requirements

## Glossary

- `Class`: is a category of objects.


## Functional Requirements

### R.001. The user should be able to create `Namespaces`, as Container for `Classes`, `Association` types,` Objects` and `Associations`

#### Business Rule
a) A `Namespace` may extend another `Namespace`.
b) A `Namespace` may have other `Namespace's` contents 

### R.002. The user should be able to select the `Namespace` to work 


### R.003. The user should be able to define `Classes` 

#### Business Rule
a) A `Class` may have attributes and properties. These values will help to define validations and rules for a given `Object` of that `Class`


### R.004. The user should be able to define Associations types among `Classes`

This establishes a rule of potential association types between two different `Classes`.

### R.005. The user should be able to define relationships with `Associative` `Classes`

For example, in cases where you have `Classes` A and B, and a relationship N:M among them. Moreover, this relationship has an Associative Class, which has attributes, properties, and may have other types of associations with other classes.  


### R.006. The user should be able to add `Objects` of a given `Class`


### R.007. The system should issue an error if an Object is trying to be added, but it's specified Class was not defined

### R.008. The user should be able to add Associations among two `Objects`

#### Business Rule
a) The system should issue an error if an `Association` is trying to be added, but it's specified `Association` type was not defined


### R.009. The user should be able to retrieve all `Objects` in a `Namespace`

### R.010. The user should be able to retrieve an `Object` by its name.

### R.011. The user should be able to retrieve all `Objects` associated with a given `Object's` name

### R.012. The user should be able to get all retrieve all defined `Classes`

### R.013. The user should be able to get all defined `Association Types`

### R.014. The user should be able to get all `Association Types` linked to a given `Class`.

For validation and model review purposes.


### R.0??. The user should be able to 

## Non-Functional Requirements

