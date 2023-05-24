import {
  ArrayInput,
  Create,
  FileInput,
  ImageField,
  NumberInput,
  required,
  SimpleForm,
  SimpleFormIterator,
  TextInput,
  useNotify,
  useRedirect
} from 'react-admin';

export const ProjectCreate = () => {
  const notify = useNotify();
  const redirect = useRedirect();

  const onSuccess = (data: any) => {
    notify(`Project created successfully`); // default message is 'ra.notification.created'
    redirect('edit', 'projects', data.id, data);
  };

  return (
    <Create mutationOptions={{ onSuccess }}>
      <SimpleForm>
        <TextInput autoFocus source="name" fullWidth validate={required()}/>
        <FileInput source="image" validate={required()}>
          <ImageField source="src" title="title"/>
        </FileInput>
        <TextInput source="description" multiline fullWidth minRows={5}/>
        <ArrayInput source="links">
          <SimpleFormIterator inline>
            <NumberInput disabled source="id" defaultValue={0} style={{'display': 'none'}}/>
            <TextInput source="title"/>
            <TextInput source="url"/>
          </SimpleFormIterator>
        </ArrayInput>
        <ArrayInput source="tags" validate={required()}>
          <SimpleFormIterator>
            <TextInput source=''/>
          </SimpleFormIterator>
        </ArrayInput>
      </SimpleForm>
    </Create>
  );
}
