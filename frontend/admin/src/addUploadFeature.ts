import {DataProvider} from "ra-core/src/types";
import {UpdateParams} from "react-admin";

/**
 * Convert a `File` object returned by the upload input into a base 64 string.
 * That's not the most optimized way to store images in production, but it's
 * enough to illustrate the idea of data provider decoration.
 */
// const convertFileToBase64 = file => new Promise((resolve, reject) => {
//     const reader = new FileReader();
//     reader.readAsDataURL(file.rawFile);
//
//     reader.onload = () => resolve(reader.result);
//     reader.onerror = reject;
// });

const uploadImage = async (dataProvider: DataProvider, resource: string, params: UpdateParams) => {
  if (resource === 'projects' || resource === 'skills') {
    if (params.data.hasOwnProperty('image')) {
      const imageData = params.data.image;
      delete params.data.image;

      if (imageData != null && !imageData.hasOwnProperty('id')) {
        let formData = new FormData();
        let rawFile = imageData.rawFile;
        formData.append('file', rawFile, rawFile.name);

        const result = await dataProvider.sendForm("storage", formData);

        params.data.imageId = result.data.id;
      }
      else {
        params.data.imageId = imageData?.id;
      }
    }
  }
}

const addUploadFeature =  (dataProvider: DataProvider) => ({
  ...dataProvider,
  create:  async (resource: string, params: UpdateParams) => {
    await uploadImage(dataProvider, resource, params);

    return await dataProvider.create(resource, params);
  },
  update: async (resource: string, params: UpdateParams) => {
    await uploadImage(dataProvider, resource, params);

    return await dataProvider.update(resource, params);
  },
});


export default addUploadFeature;
