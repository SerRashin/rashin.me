import { Create, SimpleForm, TextInput } from 'react-admin';

export const SectionCreate = () => (
  <Create>
    <SimpleForm>
      <TextInput source="name" />
    </SimpleForm>
  </Create>
);
