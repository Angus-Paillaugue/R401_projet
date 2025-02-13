export function renderTemplate(template, variables) {
  return template.replace(
    /\{\{(\w+)\}\}/g,
    (match, key) => variables[key].toString() || match
  );
}
