const fhirpath = require('fhirpath');

// Read input from command line arguments
const resource = JSON.parse(process.argv[2]);
const expression = process.argv[3];

// Evaluate the FHIRPath expression
const result = fhirpath.evaluate(resource, expression);

// Output the result
console.log(JSON.stringify(result));