import { Datagrid, List, TextField } from 'react-admin';

export const EducationList = () => (
  <List>
    <Datagrid rowClick="edit">
      <TextField source="id" />
      <TextField source="institution" />
      <TextField source="faculty" />
      <TextField source="specialization" />
    </Datagrid>
  </List>
);
