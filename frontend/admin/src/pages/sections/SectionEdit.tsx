import { Edit, SimpleForm, TextInput } from 'react-admin';

export const SectionEdit = () => (
  <Edit>
    <SimpleForm>
      <TextInput source="name" />
    </SimpleForm>
  </Edit>
);
