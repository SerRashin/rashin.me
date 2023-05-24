import { Datagrid, EmailField, List, TextField } from 'react-admin';

export const UserList = () => (
  <List>
    <Datagrid rowClick="edit">
      <TextField source="id" />
      <EmailField source="email" />
      <TextField source="firstName" />
      <TextField source="lastName" />
    </Datagrid>
  </List>
);
