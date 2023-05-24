import { Datagrid, List, TextField } from 'react-admin';

export const JobList = () => (
  <List>
    <Datagrid rowClick="edit">
      <TextField source="id" />
      <TextField source="name" />
      <TextField source="type" />
      <TextField source="company.name" />
    </Datagrid>
  </List>
);
