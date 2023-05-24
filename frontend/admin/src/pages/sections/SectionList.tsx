import { Datagrid, List, TextField } from 'react-admin';

export const SectionList = () => (
  <List>
    <Datagrid rowClick="edit">
      <TextField source="id" />
      <TextField source="name" />
    </Datagrid>
  </List>
);
